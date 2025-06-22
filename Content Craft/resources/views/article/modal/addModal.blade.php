
    {{-- Add Article --}}
    <div class="modal view-modal" id="addArticleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <h3 class="p-3">Add Article</h3>
                <form id="addArticleForm">
                    <div class="w-100 d-flex ">
                        <div class="w-100">
                            <div class="form-outline mb-3 signup-input-div">
                                <label class="form-label">Title</label>
                                <input required type="text" id="title" class="form-control " />
                            </div>
                            <div class="form-outline mb-3 signup-input-div">
                                <label class="form-label">Content</label>
                                <input required type="text" id="content" class="form-control " />
                            </div>
                            <div class="d-flex justify-content-end m-3 ">
                                <button type="button" class="btn btn-secondary" id="closeModal">Close</button>
                                <button type="button" class="btn " id="addArticleBtn"
                                    data-url="{{ route('article.store') }}">Add Article</button>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

