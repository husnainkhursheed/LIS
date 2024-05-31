@if (Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
        {{ Session::get('message') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('alert-message').style.display = 'none';
        }, 5000);
    </script>
@endif

@if ($errors->any())
    <div class="alert alert-danger" id="validation-errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('validation-errors').style.display = 'none';
        }, 6000);
    </script>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" id="error-message">
        {{ Session::get('error') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('error-message').style.display = 'none';
        }, 5000); 
    </script>
@endif

