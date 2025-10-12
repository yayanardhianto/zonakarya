@extends('admin.master_layout')
@section('title')
    <title>{{ __('WhatsApp Settings') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('WhatsApp Settings') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('WhatsApp Settings') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- <h4 class="card-title">{{ __('WhatsApp Settings') }}</h4> -->
                                <p class="card-subtitle text-muted">{{ __('Configure WhatsApp integration and QR code generation') }}</p>
                            </div>
                            <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ __('WhatsApp Connection Status') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="info" class="alert alert-info">
                                            {{ __('Click "Check Status" to verify your WhatsApp connection') }}
                                        </div>
                                        
                                        <div id="tombol" class="mb-3">
                                            <button type="button" class="btn btn-primary" onclick="cek_status()">
                                                <i class="fas fa-check-circle me-2"></i>
                                                {{ __('Check Status') }}
                                            </button>
                                        </div>
                                        
                                        <div id="qrcode" class="text-center"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Hidden input fields for configuration -->
                                <input type="hidden" id="devicename" value="byru">
                                <input type="hidden" id="deviceid" value="158">
                                <input type="hidden" id="userid" value="180">
                                <input type="hidden" id="email" value="banksat5@yahoo.com">
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ __('Instructions') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <ol class="list-group list-group-numbered">
                                            <li class="list-group-item">{{ __('Click "Check Status" to verify connection') }}</li>
                                            <li class="list-group-item">{{ __('If not connected, click "Show QR Code"') }}</li>
                                            <li class="list-group-item">{{ __('Scan QR code with your WhatsApp') }}</li>
                                            <li class="list-group-item">{{ __('Wait for connection confirmation') }}</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
<style>
    #qrcode {
        margin: 20px 0;
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
    
    #qrcode canvas {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
    }
    
    .list-group-item {
        border: none;
        padding: 0.5rem 0;
    }
</style>
@endpush

@push('js')
<script>
// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    console.log('WhatsApp settings page loaded');
});
</script>
<script>
function cek_status(){
    let devicename  = document.getElementById('devicename').value;
    let deviceid    = document.getElementById('deviceid').value;
    let userid      = document.getElementById('userid').value;
    let email       = document.getElementById('email').value;
    
    var postData = {
        newsession: true,
        devicename: devicename,
        deviceid: deviceid,
        userid: userid,
        email: email
    };

    // Show loading state
    $("#info").html('<div class="spinner-border spinner-border-sm me-2" role="status"></div>{{ __("Checking status...") }}');
    $("#tombol").html('');

    $.ajax({
        url: '{{ route("admin.whatsapp-proxy") }}',
        type: 'POST',
        data: {
            action: 'cek_status',
            _token: '{{ csrf_token() }}',
            ...postData
        },
        success: function(response) {
            console.log('Full Response:', response);

            if (response.success) {
                var jsonResponse = JSON.parse(response.data);

                if (jsonResponse.errorcode === '0000' || jsonResponse.errorcode === '0111') {
                    var data = jsonResponse.data;
                    console.log('Status data:', data);
                    
                    if (data == 'Connected'){
                        $("#info").html('<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ __("Your WhatsApp number is already connected") }}</div>');
                        $("#tombol").html('');
                    } else {
                        // Handle LOGGED OUT or other non-connected states
                        var $button = $('<button></button>')
                        .text('{{ __("Show QR Code") }}')
                        .attr('id', 'dynamicButton')
                        .addClass('btn btn-success')
                        .on('click', function() {
                            get_qr();
                        });

                        $("#tombol").html($button);
                        $("#info").html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>{{ __("WhatsApp is not connected. Click the button below to show QR code.") }}</div>');
                    }
                } else {
                    $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Failed to retrieve status. Please try again later.") }}</div>');
                    console.error('Error Code:', jsonResponse.errorcode);
                    console.error('Error Message:', jsonResponse.errormsg);
                }
            } else {
                $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Proxy error: ") }}' + response.error + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Request Error:', status, error);
            $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Network error. Please check your connection and try again.") }}</div>');
        }
    });
}

function get_qr(){
    let devicename  = document.getElementById('devicename').value;
    let deviceid    = document.getElementById('deviceid').value;
    let userid      = document.getElementById('userid').value;
    let email       = document.getElementById('email').value;
    
    var postData = {
        newsession: true,
        devicename: devicename,
        deviceid: deviceid,
        userid: userid,
        email: email
    };
    
    // Show loading state
    $("#info").html('<div class="spinner-border spinner-border-sm me-2" role="status"></div>{{ __("Generating QR code...") }}');
    $("#qrcode").html('');
    
    $.ajax({
        url: '{{ route("admin.whatsapp-proxy") }}',
        type: 'POST',
        data: {
            action: 'get_qr',
            _token: '{{ csrf_token() }}',
            ...postData
        },
        success: function(response) {
            console.log('Full Response:', response);

            if (response.success) {
                var jsonResponse = JSON.parse(response.data);

                if (jsonResponse.errorcode === '0000') {
                    var data = jsonResponse.data;
                    console.log('QR data:', data);
                    
                    if (data == null || data == ''){
                        $("#info").html('<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ __("Your WhatsApp number is already connected") }}</div>');
                    } else if (data == 'LOGGED OUT') {
                        $("#info").html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>{{ __("WhatsApp is logged out. Please wait for QR code...") }}</div>');
                        // Call get_qr to get the actual QR code data
                        get_qr();
                    } else {
                        // Generate QR code using server-side approach
                        console.log('Generating QR code for:', data);
                        generateServerSideQR(data);
                    }
                } else {
                    $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Failed to retrieve QR code. Please try again later.") }}</div>');
                    console.error('Error Code:', jsonResponse.errorcode);
                    console.error('Error Message:', jsonResponse.errormsg);
                }
            } else {
                $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Proxy error: ") }}' + response.error + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Request Error:', status, error);
            $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Network error. Please check your connection and try again.") }}</div>');
        }
    });
}

// Function to generate QR code using server-side approach
function generateServerSideQR(qrData) {
    console.log('generateServerSideQR called with data:', qrData);
    
    // Show loading state
    $("#info").html('<div class="spinner-border spinner-border-sm me-2" role="status"></div>{{ __("Generating QR code...") }}');
    
    // Make AJAX request to generate QR code server-side
    $.ajax({
        url: '{{ route("admin.whatsapp-proxy") }}',
        type: 'POST',
        data: {
            action: 'generate_qr_image',
            qr_data: qrData,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('QR generation response:', response);
            if (response.success) {
                // Display the QR code image
                document.getElementById("qrcode").innerHTML = `
                    <div class="text-center">
                        <img src="${response.qr_image}" alt="QR Code" class="img-fluid" style="max-width: 256px; height: auto;">
                    </div>
                `;
                $("#info").html('<div class="alert alert-info"><i class="fas fa-qrcode me-2"></i>{{ __("Please scan the following QR Code using your WhatsApp application.") }}</div>');
            } else {
                console.error('QR generation failed:', response.error);
                $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Failed to generate QR code: ") }}' + (response.error || '{{ __("Unknown error") }}') + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('QR Code generation error:', error);
            console.error('Response:', xhr.responseText);
            $("#info").html('<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>{{ __("Failed to generate QR code. Please try again.") }}</div>');
        }
    });
}
</script>
@endpush
