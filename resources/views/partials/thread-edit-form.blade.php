@can('update', $thread)
  <div class="modal fade" id="thread-edit-form-modal" tabindex="-1"
       data-populate-url="{{route('discuss.thread.populate', $thread)}}">
    <div class="modal-dialog">
      <form action="{{route('discuss.thread.update', $thread)}}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Thread</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

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
    ($ => {
      const $modal = $('#thread-edit-form-modal');
      $modal.on('show.bs.modal', function () {
        $.get($modal.data('populate-url')).then(response => {
          $modal.find('[name=title]').val(response.title);
          $modal.find('[name=body]').val(response.body);
        });
      });


      $modal.find('form').handleForm(xhr => xhr.then(response => {
        if (response.success) {
          $modal.modal('hide');
          $('.thread-title').html(response.title);
          $('.thread-body').html(response.body);
        }
      }));
    })(jQuery);
  </script>
@else
  <div class="modal fade" id="thread-edit-form-modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Discussion</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          Please login to create a discussion

        </div>
      </div>
    </div>
  </div>
@endcan
