<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogComment;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;

class BlogController extends Controller {
    use GlobalMailTrait;
    public function index(Request $request) {
        $query = Blog::select('id', 'blog_category_id', 'slug', 'image', 'created_at')->with([
            'translation'          => function ($query) {
                $query->select('blog_id', 'title');
            },
            'category'             => function ($query) {
                $query->select('id', 'slug');
            },
            'category.translation' => function ($query) {
                $query->select('blog_category_id', 'title');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->active()->latest();

        $query->when($request->filled('search'), function ($query) use ($request) {
            $query->whereHas('translations', function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
                $query->orWhere('description', 'like', '%' . $request->search . '%');
            });
        });

        $query->when($request->filled('category'), function ($query) use ($request) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        });

        $query->when($request->filled('tag'), function ($query) use ($request) {
            $tag = $request->tag;
            $query->whereJsonContains('tags', [['value' => $tag]]);
        });

        $blog_per_age = cache('CustomPagination')?->blog_list ?? CustomPagination::where('section_name', 'Blog List')->value('item_qty');

        $blogs = $query->paginate($blog_per_age)->withQueryString();

        $popular_blogs = $this->popularBLogs();

        $topTags = $this->topTags();

        $categories = $this->categories();

        return view('frontend.pages.blog.index', compact('blogs', 'popular_blogs', 'topTags', 'categories'));
    }

    private function categories() {
        return BlogCategory::select('id', 'slug')->with(['translation' => function ($query) {
            $query->select('blog_category_id', 'title');
        }])->withCount(['posts' => function ($query) {
            $query->active();
        }])->whereHas('posts', function ($query) {
            $query->active();
        })->active()->latest()->get();
    }

    private function popularBLogs($perPage = 3) {
        return Blog::select('id', 'slug', 'image', 'created_at')->with([
            'translation' => function ($query) {
                $query->select('blog_id', 'title');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->active()->popular()->latest()->take($perPage)->get();
    }
    private function topTags() {
        $tagsData = Blog::select('tags')->whereHas('category', function ($query) {
            $query->active();
        })->active()->get();

        $flatTags = [];
        foreach ($tagsData as $tagsEntry) {
            $tags = json_decode($tagsEntry->tags, true);
            $flatTags = array_merge($flatTags, $tags ?? []);
        }

        $tagCounts = array_count_values(array_column($flatTags, 'value'));

        arsort($tagCounts);

        return array_slice($tagCounts, 0, count($tagCounts), true);
    }
    public function show($slug) {
        $blog = Blog::select('id', 'admin_id', 'tags', 'blog_category_id', 'slug', 'image', 'created_at')->with([
            'translation'          => function ($query) {
                $query->select('blog_id', 'title', 'description', 'seo_title', 'seo_description');
            }, 'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'category'             => function ($query) {
                $query->select('id','slug');
            },
            'category.translation' => function ($query) {
                $query->select('blog_category_id', 'title');
            },
        ])->withCount(['comments' => function ($query) {
            $query->where('parent_id', 0)->active();
        }])->whereHas('category', function ($query) {
            $query->active();
        })->active()->where('slug', $slug)->first();

        if (!$blog) {
            abort(404);
        }

        $popular_blogs = $this->popularBLogs();
        $topTags = $this->topTags();
        $categories = $this->categories();

        $tagString = '';
        if ($blog?->tags) {
            $tags = json_decode($blog?->tags, true);
            $tagString = implode('', array_map(function ($tag) {
                return '<li class="text-capitalize"><a href="' . route('blogs', ['tag' => html_decode($tag['value'])]) . '">' . $tag['value'] . '</a></li>';
            }, $tags));
        }
        $nextPost = Blog::select('id', 'slug')->whereHas('category', function ($query) {
            $query->active();
        })->active()->where('id', '>', $blog->id)->first();

        $prevPost = Blog::select('id', 'slug')->whereHas('category', function ($query) {
            $query->active();
        })->active()->where('id', '<', $blog->id)->orderBy('id', 'desc')->first();

        $comment_per_age = cache('CustomPagination')?->blog_comment ?? CustomPagination::where('section_name', 'Blog Comment')->value('item_qty');

        $comments = BlogComment::withNested()->where(['blog_id'=> $blog->id,'parent_id'=> 0])->active()->latest()->paginate($comment_per_age);

        return view('frontend.pages.blog.details', compact('blog', 'popular_blogs', 'topTags', 'categories', 'tagString', 'nextPost', 'prevPost', 'comments'));
    }

    public function blogCommentStore(Request $request, $slug) {
        $blog = Blog::with('admin')->whereSlug($slug)->active()->firstOrFail();

        $setting = cache()->get('setting');

        $rules = [
            'comment'              => 'required|string|max:10000',
            'g-recaptcha-response' => $setting?->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];
        $messages = [
            'comment.required'              => __('Comment is required'),
            'comment.string'                => __('Comment should be string'),
            'comment.max'                   => __('Comment should be less than 10000 characters'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ];

        $request->validate($rules, $messages);

        $user = userAuth();

        $comment = $blog->comments()->create([
            'parent_id' => $request->query('parent_id',0),
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'image'   => $user->image ?? $setting?->default_avatar,
            'comment' => $request->comment,
        ]);

        $approved_status = cache('setting')?->comments_auto_approved ?? Setting::where('key', 'comments_auto_approved')->select('value')->first()->value;

        if ($approved_status == 'active') {
            $comment->status = true;
            $comment->save();
            $notification = __('Comment Added Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
        } else {
            try {
                $str_replace = [
                    'admin_name' => $blog?->admin?->name,
                    'user_name'  => $comment->name,
                    'link'       => route('admin.blog-comment.show', $blog?->id),
                    'blog_title' => $blog?->title,
                ];
                [$subject, $message] = $this->fetchEmailTemplate('blog_comment', $str_replace);
                $this->sendMail($user->email, $subject, $message);
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            $notification = __('Comment Added, wait for admin approval');
            $notification = ['message' => $notification, 'alert-type' => 'info'];
        }

        return redirect()->back()->with($notification);
    }
}
