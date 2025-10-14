@extends('admin.master_layout')
@section('title')
    <title>{{ __('Our Team') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Our Team') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Our Team') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    @adminCan('team.management')
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Team Section Settings') }}</h6>
                                </div>
                                <div class="card-body">
                                    <form id="team-section-title-form" action="{{ route('admin.ourteam.update-section-title') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <x-admin.form-input type="text" id="team_section_title" name="team_section_title"
                                                label="{{ __('Team Section Title') }}" placeholder="{{ __('Enter Team Section Title') }}"
                                                value="{{ $teamSectionTitle ?? 'Our Team Behind The Studio' }}" required="true" />
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> {{ __('Update Title') }}
                                        </button>
                                    </form>
                                    
                                    <hr>
                                    
                                    <form class="on-change-submit" action="{{ route('admin.contact.ourteam') }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="d-flex align-items-center mb-0">
                                            <input type="hidden" value="inactive" name="contact_team_member"
                                                class="custom-switch-input">
                                            <input {{ $setting?->contact_team_member == 'active' ? 'checked' : '' }}
                                                type="checkbox" value="active" name="contact_team_member"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span
                                                class="custom-switch-description">{{ __('User Can Contact Team Member') }}</span>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endadminCan

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Our Team')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.ourteam.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @adminCan('team.management')
                                                    <th>{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($teams as $index => $team)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $team->name }}</td>
                                                    <td>{{ $team->designation }}</td>
                                                    <td><img src="{{ asset($team->image) }}" alt="{{ $team->name }}"
                                                            class="rounded-circle my-2">
                                                    </td>
                                                    <td>
                                                        <input class="change-status" data-href="{{route('admin.ourteam.status-update',$team->id)}}"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $team->status == 'active' ? 'checked' : '' }}
                                                            data-toggle="toggle" data-on="{{ __('Active') }}"
                                                            data-off="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    @adminCan('team.management')
                                                        <td>
                                                            <x-admin.edit-button :href="route('admin.ourteam.edit', $team->id)" />
                                                            <a href="{{ route('admin.ourteam.destroy', $team->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a>
                                                        </td>
                                                    @endadminCan
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Team')" route="admin.ourteam.create" create="yes"
                                                    :message="__('No data found!')" colspan="6"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('team.management')
        <x-admin.delete-modal />
    @endadminCan

    <script>
        $(document).ready(function() {
            $('#team-section-title-form').on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var formData = form.serialize();
                var submitBtn = form.find('button[type="submit"]');
                var originalText = submitBtn.html();
                
                // Disable button and show loading
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __("Updating...") }}');
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Show success message
                        showNotification('{{ __("Team section title updated successfully!") }}', 'success');
                        
                        // Re-enable button
                        submitBtn.prop('disabled', false).html(originalText);
                    },
                    error: function(xhr) {
                        // Show error message
                        var errorMessage = '{{ __("Error updating team section title") }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                        
                        // Re-enable button
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
        
        function showNotification(message, type) {
            // Create notification element
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var notification = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';
            
            // Insert at top of page
            $('.main-content').prepend(notification);
            
            // Auto remove after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
@endsection
