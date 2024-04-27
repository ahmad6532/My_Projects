 {{-- User Delete Modal --}}
            <div class="modal  view-modal" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body m-4  text-center fw-bolder ">
                            <h4>Do You Want to Delete?</h4>
                        </div>
                        <div class="modal-footer">
                            <form id="confirmDeleteForm" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-secondary" id="closeModal">Close</button>
                                <button type="submit" class="btn " id="confirmDeleteBtn">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>