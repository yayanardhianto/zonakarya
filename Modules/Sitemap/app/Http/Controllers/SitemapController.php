<?php

namespace Modules\Sitemap\app\Http\Controllers;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Modules\Blog\app\Models\Blog;
use App\Http\Controllers\Controller;
use Modules\Shop\app\Models\Product;
use Modules\Project\app\Models\Project;
use Modules\Service\app\Models\Service;

class SitemapController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('sitemap.management');
        return view('sitemap::index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store() {
        checkAdminHasPermissionAndThrowException('sitemap.management');
        $sitemap = Sitemap::create();

        $pages = [ '/','/about','/contact','/portfolios','/services','/blogs','/team','/pricing','/faq','/privacy-policy','/terms-condition','/shop','/cart'];
    
        foreach ($pages as $page) {
            $sitemap->add(Url::create($page)->setLastModificationDate(now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        foreach (customPages() as $page) {
            $sitemap->add(Url::create("/page/{$page->slug}")->setLastModificationDate($page?->updated_at ?? now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        $products = Product::select('slug')->latest()->whereHas('category', function ($query) {
            $query->active();
        })->active()->take(10)->get();
        foreach($products as $product){
            $sitemap->add(Url::create(url("shop/$product->slug"))->setLastModificationDate(now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        $blogs = Blog::select('slug')->latest()->whereHas('category', function ($query) {
            $query->active();
        })->active()->take(10)->get();
        foreach($blogs as $blog){
            $sitemap->add(Url::create(url("blogs/$blog->slug"))->setLastModificationDate(now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        $services = Service::select('slug')->latest()->active()->take(10)->get();
        foreach($services as $service){
            $sitemap->add(Url::create(url("services/$service->slug"))->setLastModificationDate(now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        $portfolios = Project::select('slug')->latest()->whereHas('service', function ($query) {
            $query->active();
        })->active()->take(10)->get();
        foreach($portfolios as $portfolio){
            $sitemap->add(Url::create(url("portfolios/$portfolio->slug"))->setLastModificationDate(now())->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
        return back()->with(['message' => 'Sitemap generated!', 'alert-type' => 'success']);
    }
}
