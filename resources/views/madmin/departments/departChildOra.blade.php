<ul class="text-info">
    @foreach ($children as $child)
        @if($child->filial_code === $filial_code)

            <li>
                {{ $child->getName->getReplace($child->getName->title??'-')??'-' }}
            </li>

            @if (count($child->children))

                @include('madmin.departments.departChildOra',['children' => $child->children, 'filial_code' => $filial_code])

            @endif

        @endif

    @endforeach
</ul>
