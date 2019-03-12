<!-- Editable table -->
    <div class="card">
    <h3 class="card-header text-center font-weight-bold text-uppercase py-4">{{ $tableTitle }}</h3>
        <div class="card-body">
          <div id="table" class="table-editable">
            <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i class="fas fa-plus fa-2x"
                  aria-hidden="true"></i></a></span>
            <table class="table table-bordered table-responsive-md table-striped text-center">
              <tr>
                <th class="text-center">{{$itemName}} id</th>
                <th class="text-center">{{$itemName}}</th>
                <th class="text-center">Save</th>
                <th class="text-center">Remove</th>
              </tr>



          @foreach ($items as $item)
              <tr class="row-item">
                <td class="pt-3-half item-id" contenteditable="false">{{ $item->id }}</td>
                <td class="pt-3-half item-name" contenteditable="true">{{ $item->name }}</td>
                <td>
                    <span class="table-save"><button type="button" class="btn btn-primary btn-rounded btn-sm my-0">Save</button></span>
                </td>
                <td>
                    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0">Remove</button></span>
                </td>
              </tr>
            @endforeach
              
            </table>
          </div>
        </div>
      </div>
      <!-- Editable table -->
      <div class="card">
          
          <div class="card-body">
              <h4 class="card-title">Add {{$itemName}}</h4>
              <form id="addForm">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="{{$itemName}} name" required="required" >
                    </div>
                    <button type="submit" class="btn btn-primary">add</button>
                </form>
          </div>
      </div>