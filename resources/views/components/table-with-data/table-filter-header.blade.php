@if(!empty($displayedFields) && is_array($displayedFields))
<tr>
    @foreach($displayedFields as $displayedFieldArray)
        <th class="text-center text-dark text-xs font-weight-bolder">
            <label for="filter_{{ $displayedFieldArray['field'] }}">{{ $displayedFieldArray['field_title'] }}</label>
            @if(!empty($indexRouteName))
                <a href="{{ route($indexRouteName, ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'desc']) }}"><i class="fa fa-arrow-down @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'desc') text-danger @endif"></i></a>
                <a href="{{ route($indexRouteName, ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'asc']) }}"><i class="fa fa-arrow-up @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'asc') text-danger @endif"></i></a>
            @endif
        </th>
    @endforeach

    <th class="text-center text-dark text-xs font-weight-bolder opacity-8">{{ __('Дії') }}</th>
</tr>
@endif
