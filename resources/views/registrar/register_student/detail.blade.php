<ul class="nav nav-tabs">
    <li class="active"><a href="#pre_elementary_tab" data-toggle="tab">Pre - School<i class="sf"></i></a></li>
    <li><a href="#elementary_tab" data-toggle="tab">Elementary <i class="sf"></i></a></li>
    <li><a href="#junior_high_tab" data-toggle="tab">Junior High School <i class="af"></i></a></li>
    <li><a href="#senior_hig_tab" data-toggle="tab">Senior High School<i class="sf"></i></a></li>
</ul>
<form id="accountForm" method="post" class="form-horizontal">
	<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
    <div class="tab-content">
        <div class="tab-pane active" id="pre_elementary_tab"><br>
            <table id="table_pre_elementary" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th> {{ Lang::get("registrar.id_number") }}</th>
                        <th> {{ Lang::get("registrar.name") }}</th>
                        <th> {{ Lang::get("registrar.classification_level") }}</th>
                        <th>{{ Lang::get("form.action") }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="tab-pane" id="elementary_tab"><br>
            <table id="table_elementary" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th> {{ Lang::get("registrar.id_number") }}</th>
                        <th> {{ Lang::get("registrar.name") }}</th>
                        <th> {{ Lang::get("registrar.classification_level") }}</th>
                        <th>{{ Lang::get("form.action") }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="tab-pane" id="junior_high_tab"><br>
            <table id="table_junior_high" class="table table-striped table-hover">
                <thead>
                    <tr>
                         <th> {{ Lang::get("registrar.id_number") }}</th>
                        <th> {{ Lang::get("registrar.name") }}</th>
                        <th> {{ Lang::get("registrar.classification_level") }}</th>
                        <th>{{ Lang::get("form.action") }}</th>                          
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="tab-pane" id="senior_hig_tab"><br>
            <table id="table_senior_high" class="table table-striped table-hover">
                <thead>
                    <tr>
                         <th> {{ Lang::get("registrar.id_number") }}</th>
                        <th> {{ Lang::get("registrar.name") }}</th>
                        <th> {{ Lang::get("registrar.classification_level") }}</th>
                        <th>{{ Lang::get("form.action") }}</th>                          
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</form>