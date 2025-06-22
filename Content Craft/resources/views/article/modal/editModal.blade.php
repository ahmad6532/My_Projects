

    {{-- edit Article --}}
    <div class="modal view-modal" id="editArticleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <h3 class="p-3">Update Article</h3>
                <form id="editArticleForm">
                    <div class="w-100 d-flex ">
                        <div class="w-100">
                            <input required type="hidden" id="updateId" class="form-control " />
                            <div class="form-outline mb-3 signup-input-div">
                                <label class="form-label">Title</label>
                                <input required type="text" id="updateTitle" class="form-control " />
                            </div>
                            <div class="form-outline mb-3 signup-input-div">
                                <label class="form-label">Content</label>
                                <input required type="text" id="updateContent" class="form-control " />
                            </div>
                            <div class="d-flex justify-content-end m-3 ">
                                <button type="button" class="btn btn-secondary" id="closeModal">Close</button>
                                <button type="button" class="btn " id="editArticleBtn"
                                    data-url="{{ route('article.update', '') }}">Update Article</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>