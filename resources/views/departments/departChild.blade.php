<ul>

    @foreach($childs as $child)

        <li>

            {{ $child->title }}

            @if(count($child->childs))

                @include('departments.departChild',['childs' => $child->childs])

            @endif

        </li>

    @endforeach

</ul>