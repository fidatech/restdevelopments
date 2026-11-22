@php
    $path = trim(request()->path(), '/');

    // Route → Label mapping
    $map = [
        '' => 'Home',
        'about' => 'About',
        'latest-launches' => 'Latest Launches',
        'projects' => 'Projects',
        'blog' => 'Blog',
        'careers' => 'Careers',
        'contact' => 'Contact',
        'team' => 'Team',
    ];

    // If current path not in map, do nothing
    if (!array_key_exists($path, $map)) {
        return;
    }
@endphp

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    }
@endverbatim

@if($path !== '')
@verbatim
    ,{
      "@type": "ListItem",
      "position": 2,
      "name": "{{ $map[$path] }}",
      "item": "{{ url($path) }}"
    }
@endverbatim
@endif

@verbatim
  ]
}
</script>
@endverbatim
