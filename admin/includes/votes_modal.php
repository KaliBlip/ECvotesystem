<!-- Reset -->
<div class="modal fade" id="reset">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reset Votes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form class="form-horizontal" method="POST" action="votes_reset.php">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-danger fa-4x mb-4"></i>
                        <h5 class="mb-4">Warning: This will delete all votes and cannot be undone.</h5>
                        <p>Are you sure you want to reset all votes?</p>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" name="reset">
                            <i class="fas fa-trash"></i> Reset Votes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>