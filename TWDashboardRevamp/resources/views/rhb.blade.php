<div class="col-md-4 col-lg-4 col-xl-4 order-md-1 ">

    <div class="content w-100">


        <!-- Side Content -->

        <div id="side-content">

            <br><br>


            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side content-side-full w-100">
                    <ul class="nav-main">
                        <li class="nav-main">
                            <a type="button" id="bEdit1" href="{{route('editaccountbtn')}}"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height">
                                <span>{{ strtoupper(__('Edit Account')) }}</span>
                            </a>
                        </li>
                        <br>
                        <li class="nav-main">
                            <a type="button" id="bTrance1"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height"
                                    href="{{route('transbtn')}}">
                                <span>{{ strtoupper(__('Transactions')) }}</span>
                            </a>
                        </li>
                        <br>

                        <li class="nav-main">
                            <input type="text" id="count" name="dd"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height"
                                    pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="{{ strtoupper(__('Fill Amount')) }}">



                        </li>
                        <br>
                        <li class="nav-main">
                            <button type="submit" id="reeefill"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height">
                                <span>{{ strtoupper(__('Add Player Credits')) }}</span>
                            </button>
                        </li>

                        <br>
                        <li class="nav-main">
                            <a type="button" id="reeeeedeem"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height">
                                <span>{{ strtoupper(__('Redeem Points')) }}</span>
                            </a>
                        </li>
                        <br><br><br><br><br>
                        <li class="nav-main">
                            <a type="button" id="bLang"
                                    class="btn btn-alt-secondary header-btn w-100 sidebar-height"
                                    href="{{route('changeLang', app()->getLocale() == "es" ? "en" :"es")}}">
                                <span>{{app()->getLocale() == "es"?"English":"Español"}}</span>
                            </a>

                        </li>
                    </ul>

                </div>
                <!-- END Side Navigation -->
            </div>
        </div>
    </div>
</div>

