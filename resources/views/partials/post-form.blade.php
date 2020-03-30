@auth
  <div class="modal fade" id="post-form-modal" tabindex="-1">
    <div class="modal-dialog">
      <form action="{{route('discuss.post.create', $thread)}}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Reply</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <textarea name="body" rows="10" class="form-control" autofocus></textarea>
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
    $('#post-form-modal form').handleForm(xhr => xhr.then(response => {
      if (response.success) {
        location.reload();
      }
    }));
  </script>
@endauth
