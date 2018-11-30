{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach($items as $item)
        <url>
            <loc>{{ $item['link'] }}</loc>
            <news:news>
                <news:publication>
                    <news:name>{!! $channel['title'] !!}</news:name>
                    <news:language>{{ $channel['lang'] }}</news:language>
                </news:publication>
                <news:genres>{{ $item['category']['category'] }}</news:genres>
                <news:publication_date>{{ $item['pubdate'] }}</news:publication_date>
                <news:title>{!! $item['title'] !!}</news:title>
                <news:keywords>{{ $item['category']['tags'] }}</news:keywords>
            </news:news>
        </url>
    @endforeach
</urlset>