<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Position</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="positions_add.php">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="dept_category">Department Category</label>
                        <select class="form-control" id="dept_category" name="dept_category" required>
                            <option value="">Select Department Category</option>
                            <option value="all">All</option>
                            <option value="Pasag">PASAG</option>
                            <option value="Puganms">PUGANMS</option>
                            <option value="COMPSA">COMPSA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gender_class">Gender Classification</label>
                        <select class="form-control" id="gender_class" name="gender_class" required>
                            <option value="">Select Gender Classification</option>
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="max_vote">Maximum Vote</label>
                        <input type="number" class="form-control" id="max_vote" name="max_vote" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" name="add">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Position</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="positions_edit.php">
                    <input type="hidden" class="id" name="id">
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_dept_category">Department Category</label>
                        <select class="form-control" id="edit_dept_category" name="dept_category" required>
                            <option value="">Select Department Category</option>
                            <option value="all">All</option>
                            <option value="Pasag">PASAG</option>
                            <option value="Puganms">PUGANMS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_gender_class">Gender Classification</label>
                        <select class="form-control" id="edit_gender_class" name="gender_class" required>
                            <option value="">Select Gender Classification</option>
                            <option value="all">All</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_max_vote">Maximum Vote</label>
                        <input type="number" class="form-control" id="edit_max_vote" name="max_vote" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success" name="edit">
                            <i class="fas fa-check"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Position</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="positions_delete.php">
                    <input type="hidden" class="id" name="id">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                        <h5>Are you sure you want to delete this position?</h5>
                        <h4 class="bold description mt-2"></h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger" name="delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



     