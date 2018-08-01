<form action="/login/" method="post">
    <input type="text" name="username">
    <input type="password" name="password">
    {{csrf_field()}}
    <input type="submit">
</form>