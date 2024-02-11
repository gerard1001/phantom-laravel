<h1>{{$heading}}</h1>
@foreach($listings as $listing)
    <a href="/listings/{{$listing['id']}}"><h2>{{$listing['title']}}</h2></a>
    <p>{{$listing['description']}}</p>
    <hr>
@endforeach