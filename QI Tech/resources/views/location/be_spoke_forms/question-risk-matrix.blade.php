<div class="risk-matrix form-group question_{{$question->id}}">
    <label for="question_{{$question->id}}">{{$question->question_title}}</label>
    <div class="border-checkbox-radio">
    <input type="hidden" name="question_{{$question->id}}" id="question_{{$question->id}}" value="1" class="risk-matrix-value form_question">
    <p class="severity">Severity <i class="fa fa-arrow-right"></i></p>
    <p class="left likelihood"><i class="fa fa-arrow-up"></i> <br>Likelihood</p>
    <table class="table-bordered risk-martix-table">
        <thead>
            <tr>
                <th class="heading"></th>
                <th class="heading">1<br>Insignificant</th>
                <th class="heading">2<br>Minor</th>
                <th class="heading">3<br>Moderate</th>
                <th class="heading">4<br>Major</th>
                <th class="heading">5<br>Death</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="heading">1<br>Rare</td>
                <td class="td-1-1"> <a href="#" class="value value-1-1" data-value="1-1">1</a> </td>
                <td class="td-1-2"> <a href="#" class="value value-1-2" data-value="1-2">2</a> </td>
                <td class="td-1-3"> <a href="#" class="value value-1-3" data-value="1-3">3</a> </td>
                <td class="td-1-4"> <a href="#" class="value value-1-4" data-value="1-4">4</a> </td>
                <td class="td-1-5"> <a href="#" class="value value-1-5" data-value="1-5">5</a> </td>
            </tr>
            <tr>
                <td class="heading">2<br>Unlikely</td>
                <td class="td-2-1"> <a href="#" class="value value-2-1" data-value="2-2">2</a> </td>
                <td class="td-2-4"> <a href="#" class="value value-2-4" data-value="2-4">4</a> </td>
                <td class="td-2-6"> <a href="#" class="value value-2-6" data-value="2-6">6</a> </td>
                <td class="td-2-8"> <a href="#" class="value value-2-8" data-value="2-8">8</a> </td>
                <td class="td-2-10"> <a href="#" class="value value-2-10" data-value="2-10">10</a> </td>
            </tr>
            <tr>
                <td class="heading">3<br>Possible</td>
                <td class="td-3-3"> <a href="#" class="value value-3-3" data-value="3-3">3</a> </td>
                <td class="td-3-6"> <a href="#" class="value value-3-6" data-value="3-6">6</a> </td>
                <td class="td-3-9"> <a href="#" class="value value-3-9" data-value="3-9">9</a> </td>
                <td class="td-3-12"> <a href="#" class="value value-3-12" data-value="3-12">12</a> </td>
                <td class="td-3-15"> <a href="#" class="value value-3-15" data-value="3-15">15</a> </td>
            </tr>
            <tr>
                <td class="heading">4<br>Likely</td>
                <td class="td-4-4"> <a href="#" class="value value-4-4" data-value="4-4">4</a> </td>
                <td class="td-4-8"> <a href="#" class="value value-4-8" data-value="4-8">8</a> </td>
                <td class="td-4-12"> <a href="#" class="value value-4-12" data-value="4-12">12</a> </td>
                <td class="td-4-16"> <a href="#" class="value value-4-16" data-value="4-16">16</a> </td>
                <td class="td-4-20"> <a href="#" class="value value-4-20" data-value="4-20">20</a> </td>
            </tr>
            <tr>
                <td class="heading">5<br>Certain</td>
                <td class="td-5-5"> <a href="#" class="value value-5-5" data-value="5-5">5</a> </td>
                <td class="td-5-10"> <a href="#" class="value value-5-10" data-value="5-10">10</a> </td>
                <td class="td-5-15"> <a href="#" class="value value-5-15" data-value="5-15">15</a> </td>
                <td class="td-5-20"> <a href="#" class="value value-5-20" data-value="5-20">20</a> </td>
                <td class="td-5-25"> <a href="#" class="value value-5-25" data-value="5-25">25</a> </td>
            </tr>

        </tbody>
    </table>
</div>
</div>