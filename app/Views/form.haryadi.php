@extends('main')

<form method="post" action="/submit">
    <?php echo csrf_field(); ?>
    <input type="text" name="title" placeholder="Enter title">
    <button type="submit">Submit</button>
</form>

@section('content')
    <h1>Welcome to the form</h1>
@endSection

@push('scripts')
    <script>
        console.log('Form page loaded');
    </script>
@endPush