<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 100%; width: auto; margin: 0;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-3" id="exampleModalLabel" style="text-align: center;width: 100%;">Information Request (Preview)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="profile-center-area">
                    {{-- <div class="container-fluid mt-5">
                        <h4>Hello</h4>
                        <p>We need your account of events regarding an incident that was reported</p>
                    </div> --}}
                    <div class="row">
                        <div class="col-sm-12 read_stages">

                        </div>
                    </div>
                    <br>
                    <b class="note-text">User information</b><br>
                    <div class="">
                        <form  class="user-questions" action="">

                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-3" id="saveModalLabel" style="text-align: center;width: 100%;">Save New Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="display: flex; align-items: center;">
                    <input type="text" name="save_questions" class="form-control save-question" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-text-btn">Save changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>

    <script>
        $(function() {
            // Preview form
            $('#exampleModal').on('show.bs.modal', function(event) {
                const statementProvider = $('.show_statement_provider .case-intelligence-container');

                const statementProviderNote = statementProvider.find('textarea#note').val();
                const stageCards = $('.show_report_section .is_visiable_to_pserson_data .card.stages');
                const stageCount = stageCards.length;

                $('.profile-center-area .note-text').text(statementProviderNote);

                // request information questions
                let questionContent = '';
                let filledInputValues = [];
                const questionFields = $('input[name="questions[]"].question');
                questionFields.each(function (){
                    if($(this).val().trim() !== ''){
                        filledInputValues.push($(this).val());
                    }
                });

                filledInputValues.forEach(function (element){
                questionContent +=
                    `<div class="form-group">
                        <label>${element}</label>
                    <textarea spellcheck="true"  class="form-control" required="" name="answer_24"></textarea>
                    <br>
                    </div>`;
                });

                questionContent +=`<div class="uploaded_files mt-2 mb-2">
                        <input type="file" name="file" readonly="" multiple="" value="" class="form-control commentMultipleFiles">
                    </div>
                    <div class="form-group">
                        <label for="note">Additional comments (optional)</label>
                        <textarea spellcheck="true"  class="form-control" readonly="" name="note"></textarea>
                    </div>
                    <br>
                    <div class="from-group">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>`;

                $('.profile-center-area .user-questions').html(questionContent);

                // checked fields
                if (stageCount > 0) {
                    let htmlContent = '';

                    stageCards.each(function(index) {
                        const i = index + 1;
                        const stage = $(this);
                        const heading = stage.find('.card-body > h5').text();
                        const subHeading = stage.find('.card-body .card-header > .form-group-name').text();

                        const checkedInputValues = [];
                        const checkedValueSelector = stage.find('.col-md-6 input[type="checkbox"]:checked + label');

                        checkedValueSelector.each(function() {
                            checkedInputValues.push($(this).text());
                        });

                        // Construct the HTML
                        htmlContent += `
                                  <div class="card stages stage_3 stage_data_${i}">
                                    <div class="card-body">
                                      <h5>${heading}</h5>
                                      <div class="group group_4">
                                        <div class="">
                                          <h5 class="form-group-name">${subHeading}</h5>
                                          <div class="row">`;

                                            checkedInputValues.forEach(function(element) {
                                                const [labelText, valueText] = element.split(':').map(text => text.trim());
                                                htmlContent += `
                                                        <div class="col-md-6">
                                                          <div class="form-group question_4">
                                                            <label for="question_4">${labelText}</label>
                                                            <input type="text" readonly class="form-control" value="${valueText}" title="" style="background-color: #e9ecef; opacity: 1">
                                                          </div>
                                                        </div>`;
                                            });

                                                htmlContent += `
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>`;
                    });

                    $('.read_stages').html(htmlContent);
                }
            });

            let currentTile = null; // Variable to store the tile being edited

            // Clear input and reset currentTile when the modal is shown
            $('#saveModal').on('show.bs.modal', function(event) {
                if (!currentTile) {
                    $('.save-question').val('');
                }
            });

            // Handle save button click
            $('.save-text-btn').on('click', function() {
                const text = $('.save-question').val();
                if (text) {
                    if (currentTile) {
                        // Update existing tile text
                        currentTile.find('.text-tile').text(text);
                        currentTile.find('.hidden-text-tile').val(text);
                        currentTile = null; // Reset currentTile after updating
                    } else {
                        // Create a new tile
                        const tile = $('<div class="text-wrap"></div>');
                        const textTile = $('<button class="text-tile" type="button"></button>').text(text);
                        const hiddenTextField = $(`<input type="hidden" class="hidden-text-tile" name=s_questions[] value="${text}">`);
                        const deleteBtn = $('<span class="delete-sfield"><i class="fa-regular fa-trash-can"></i></span>');
                        const editBtn = $('<span class="edit-sfield"><i class="fa fa-edit"></i></span>');

                        tile.append(textTile, hiddenTextField, deleteBtn, editBtn);
                        $('.questions-list').append(tile);

                    }
                    // Clear input and hide modal
                    $('.save-question').val('');
                    $('#saveModal').modal('hide');
                } else {
                    alertify
                        .alert("Alert!","Please add question.", function(){});
                }
            });

            // Event delegation for edit button
            $(document).on('click', '.edit-sfield', function() {
                const tileText = $(this).siblings('.text-tile').text();
                currentTile = $(this).closest('.text-wrap'); // Set currentTile to the parent div (text-wrap)
                $('.save-question').val(tileText);
                $('#saveModal').modal('show');
            });

            // Event delegation for delete button
            $(document).on('click', '.delete-sfield', function() {
                $(this).closest('.text-wrap').remove();
            });

            $(document).on('click', 'button.text-tile', function() {
                const tileText = $(this).text();
                const lastInput = $('#custom').find('.question').last();
                if (lastInput.val() === '') {
                    lastInput.val(tileText);
                }else{
                    $("#custom .card .card-body .fields-wrap").append(`<div class="field-container"><div class=""><div class="form-group" style="display: flex;align-items: center;"><label>Question </label>
                <span class="drag-handle">::</span><input type="text" name="questions[]" multiple="multiple" class="form-control question" data-id="" value="${tileText}" required><span class="delete-field"><i class="fa-regular fa-trash-can"></i></span>
                </div></div>
                </div>`);
                }

            });

        });

    </script>