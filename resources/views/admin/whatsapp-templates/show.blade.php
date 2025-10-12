@extends('admin.master_layout')
@section('title')
    <title>{{ __('WhatsApp Template Details') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('WhatsApp Template Details') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('WhatsApp Templates') => route('admin.whatsapp-templates.index'),
            $whatsappTemplate->name => '#',
        ]" />

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $whatsappTemplate->name }}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.whatsapp-templates.edit', $whatsappTemplate) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-edit"></i> {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('Type') }}:</strong> 
                                        <span class="badge badge-{{ $whatsappTemplate->type == 'short_call_invitation' ? 'success' : 'warning' }}">
                                            {{ ucwords(str_replace('_', ' ', $whatsappTemplate->type)) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('Status') }}:</strong> 
                                        <span class="badge badge-{{ $whatsappTemplate->is_active ? 'success' : 'danger' }}">
                                            {{ $whatsappTemplate->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><strong>{{ __('Template Message') }}</strong></label>
                                <div class="template-preview p-3 bg-light rounded">
                                    <pre style="white-space: pre-wrap; font-family: inherit;">{{ $whatsappTemplate->template }}</pre>
                                </div>
                            </div>

                            @if($whatsappTemplate->variables && count($whatsappTemplate->variables) > 0)
                                <div class="form-group">
                                    <label><strong>{{ __('Available Variables') }}</strong></label>
                                    <div class="row">
                                        @foreach($whatsappTemplate->variables as $variable)
                                            <div class="col-md-3 mb-2">
                                                <span class="badge badge-info">{{ $variable }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label><strong>{{ __('Preview with Sample Data') }}</strong></label>
                                <div class="preview-container p-3 bg-light rounded">
                                    <div id="previewMessage">
                                        {{ __('Click "Preview" to see how the message will look') }}
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info btn-sm mt-2" onclick="previewMessage()">
                                    <i class="fas fa-eye"></i> {{ __('Preview') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Template Information') }}</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('Created') }}:</strong> {{ $whatsappTemplate->created_at->format('d M Y H:i') }}</p>
                            <p><strong>{{ __('Last Updated') }}:</strong> {{ $whatsappTemplate->updated_at->format('d M Y H:i') }}</p>
                            
                            <hr>
                            
                            <h6>{{ __('Sample Data for Preview') }}</h6>
                            <div class="form-group">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="previewName" value="John Doe">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Position') }}</label>
                                <input type="text" class="form-control" id="previewPosition" value="Software Developer">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Company') }}</label>
                                <input type="text" class="form-control" id="previewCompany" value="Your Company">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Date') }}</label>
                                <input type="text" class="form-control" id="previewDate" value="{{ now()->format('d M Y') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Reason') }}</label>
                                <input type="text" class="form-control" id="previewReason" value="Not meeting requirements">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
function previewMessage() {
    const template = @json($whatsappTemplate->template);
    const name = document.getElementById('previewName').value;
    const position = document.getElementById('previewPosition').value;
    const company = document.getElementById('previewCompany').value;
    const date = document.getElementById('previewDate').value;
    const reason = document.getElementById('previewReason').value;
    
    let message = template;
    message = message.replace(/{NAME}/g, name);
    message = message.replace(/{POSITION}/g, position);
    message = message.replace(/{COMPANY}/g, company);
    message = message.replace(/{DATE}/g, date);
    message = message.replace(/{REASON}/g, reason);
    
    document.getElementById('previewMessage').innerHTML = '<pre style="white-space: pre-wrap; font-family: inherit;">' + message + '</pre>';
}
</script>
@endpush
