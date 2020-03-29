@auth
  <div class="modal fade" id="thread-form-modal" tabindex="-1">
    <div class="modal-dialog">
      <form action="{{route('discuss.create-thread')}}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create New Discussion</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <select name="category_id" id="thread-category" class="form-control" required autofocus>
                <option value="">Select category...</option>
                @foreach ($categories as $category)
                  <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <input type="text" name="title" class="form-control" id="thread-title" placeholder="Title...">
            </div>

            <div class="form-group">
              <textarea name="body" rows="10" class="form-control"></textarea>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- FIXME --}}
  <script>
    $('#thread-form-modal form').handleForm(xhr => xhr.then(response => {
      if (response.success) {
        $('#thread-form-modal').modal('hide');
        location.href = response.url;
      }
    }));
  </script>
@endauth
