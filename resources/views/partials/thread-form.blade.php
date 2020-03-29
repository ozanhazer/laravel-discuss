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
    ($ => {
      $.fn.handleForm = function (callbackFn) {
        const $form = this;
        const form = $form.get(0);

        if (form.nodeName.toLowerCase() !== 'form') {
          throw "Invalid node type. handleForm should be used on form elements only.";
        }

        const setLoading = start => {
          if (start) {
            $form.find('[type=submit]').prop('disabled', true);
            $form.find('[type=submit]').append('<i class="fa fa-refresh fa-spin ml-2"></i>');
          } else {
            $form.find('[type=submit]').prop('disabled', false);
            $form.find('[type=submit]').find('.fa-refresh').remove();
          }
        };

        const resetValidation = () => {
          $form.find('.is-invalid').removeClass('is-invalid');
          $form.find('.invalid-feedback').remove();
        };

        const addValidationMessages = errors => {
          for (const fieldName in errors) {
            const errorMessage = errors[fieldName].join('\n');
            $form.find('[name=' + fieldName + ']')
              .addClass('is-invalid')
              .after('<div class="invalid-feedback">' + errorMessage + '</div>')
          }
        };

        form.addEventListener('submit', e => {
          e.preventDefault();
          setLoading(true);
          resetValidation();

          const xhr = $.post($form.attr('action'), $form.serialize())
            .catch(response => response.status === 422 ?
              addValidationMessages(response.responseJSON.errors) :
              bootbox.alert('An unexpected error has occured. Please try again later...'))
            .always(() => setLoading(false));

          callbackFn(xhr);
        });
      };

    })(jQuery);

    $('#thread-form-modal form').handleForm(xhr => xhr.then(response => {
      if (response.success) {
        $('#thread-form-modal').modal('hide');
        location.href = response.url;
      }
    }));
  </script>
@endauth
