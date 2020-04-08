@can('change-category', new Alfatron\Discuss\Models\Thread)
  <div class="modal fade" id="change-category-form-modal" tabindex="-1">
    <div class="modal-dialog">
      <form action="#">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Change Category</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <select name="category_id" class="form-control" required autofocus>
                @foreach ($categories as $category)
                  <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>
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
    ($ => {
      const $modal = $('#change-category-form-modal');
      $modal.find('form').handleForm(xhr => xhr.then(response => {
        if (response.success) {
          $modal.modal('hide');
          location.href = response.url;
        }
      }));
    })(jQuery);
  </script>
@endcan
