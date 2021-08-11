<ul>

    @foreach($childs as $child)

        <li class="department_class text-primary text-bold" data-id="{{$child->id}}" style="list-style: none">


            {{ $child->title }}

            @if(count($child->childs))

                @include('madmin.departments.departChild',['childs' => $child->childs])

            @endif

        </li>

    @endforeach

</ul>
