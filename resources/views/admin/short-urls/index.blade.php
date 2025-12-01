@extends('admin.master_layout')

@section('title')
    <title>{{ __('URL Shortener Management') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('URL Shortener') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Short URLs') => '#',
        ]" />

        <div class="section-body">
            <!-- Create Button -->
            <div class="mb-3">
                <a href="{{ route('admin.short-urls.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('Create Short URL') }}
                </a>
            </div>

            <!-- Short URLs List -->
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Short URLs') }}</h4>
                </div>
                <div class="card-body">
                    @if($shortUrls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Short Code') }}</th>
                                        <th>{{ __('Short URL') }}</th>
                                        <th>{{ __('Original URL') }}</th>
                                        <th>{{ __('Clicks') }}</th>
                                        <th>{{ __('Created By') }}</th>
                                        <th>{{ __('Created Date') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shortUrls as $url)
                                        <tr>
                                            <td>
                                                <code class="badge bg-primary">{{ $url->code }}</code>
                                            </td>
                                            <td>
                                                <a href="{{ config('app.url') }}/{{ $url->code }}" target="_blank" class="text-decoration-none">
                                                    {{ config('app.url') }}/{{ $url->code }}
                                                    <i class="fas fa-external-link-alt ms-1" style="font-size: 0.8rem;"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <small class="text-muted" title="{{ $url->original_url }}">
                                                    {{ \Str::limit($url->original_url, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $url->click_count }}</span>
                                            </td>
                                            <td>
                                                <small>{{ $url->creator?->name ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $url->created_at->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.short-urls.edit', $url) }}" class="btn btn-warning btn-sm" title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.short-urls.destroy', $url) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $shortUrls->links() }}
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            {{ __('No short URLs created yet.') }}
                            <a href="{{ route('admin.short-urls.create') }}" class="alert-link">{{ __('Create one now') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
