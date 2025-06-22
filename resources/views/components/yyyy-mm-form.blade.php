<form method="GET" action="{{ url()->current() }}">
    <label>開始月: 
        <input type="month" name="start_month" value="{{ request('start_month') }}">
    </label>
    <label>終了月: 
        <input type="month" name="end_month" value="{{ request('end_month') }}">
    </label>
    <button type="submit">絞り込む</button>
</form>
