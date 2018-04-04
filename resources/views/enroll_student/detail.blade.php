<table class="table table-bordered table-striped" id="student_sibling_table">
    <th style="width:25%"><center>Name</center></th>

    <tbody id="student_sibling_container">
        @foreach($student_siblings_list as $student_siblings)
            <tr>
                <td>
                    <input type="hidden" id="student_siblings_id" name="student_siblings_id" value="{{{$student_siblings->id}}}" />
                    <input readOnly class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($student_siblings) ? $student_siblings->name : null) }}}" />
                </td>

            </tr>
        @endforeach
    </tbody>                    
</table>