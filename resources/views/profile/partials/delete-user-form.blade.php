@if(auth()->user()->hasRole('admin'))
<section>
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <strong>Warning!</strong> This action cannot be undone. All your data will be permanently deleted.
    </div>

    <p class="text-muted mb-4">
        <i class="bi bi-info-circle"></i> Before deleting your account, please download any data or information that you wish to retain.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        <i class="bi bi-trash-fill"></i> Delete My Account
    </button>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill"></i> Confirm Account Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    <div class="modal-body">
                        <p class="mb-3">
                            <strong>Are you absolutely sure?</strong>
                        </p>
                        <p class="text-muted mb-3">
                            Once your account is deleted, all of your resources and data will be permanently deleted. This action cannot be undone.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Enter Your Password to Confirm
                            </label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="Your password"
                                required
                                autofocus
                            />
                            @error('password', 'userDeletion')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        @csrf
                        @method('delete')
                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash-fill"></i> Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@else
<div class="alert alert-info">
    <i class="bi bi-info-circle-fill"></i>
    <strong>Account Deletion</strong> is only available for administrators. If you need to close your account, please contact an administrator.
</div>
@endif
