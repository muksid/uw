<ul>

    @foreach($childs as $child)

        <li class="department_class text-primary text-bold" data-id="{{$child->id}}" style="list-style: none">

            @if($child->ora_condition === 'A')
                <h5>
                    <span class="text-sm text-green">{{ $child->ora_parent_code }} -
                                                {{ $child->ora_code }} ({{ $child->ora_condition }})
                                            </span>
                    - {{ $child->title }}

                </h5>
            @else
                <h5 class="text-maroon">
                    <span class="text-sm">{{ $child->ora_parent_code }} -
                                                {{ $child->ora_code }} ({{ $child->ora_condition }})
                                            </span>
                    - {{ $child->title }}

                </h5>
            @endif



            @if(count($child->childs))

                @include('madmin.departments.departChild',['childs' => $child->childs])

            @endif

        </li>

    @endforeach

</ul>
