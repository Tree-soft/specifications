{!! '<' . '?php' !!}

namespace {{ $requestNamespace }};

use {{ $baseNamespace }}@if (isset($alias)) as {{ $alias }}@endif;

/**
 * {{'@author'}} Json-schema request generator
 */
class {{ $requestClass }} extends {{ $alias ?? $baseClass }}
{
@if (isset($headerSchema))
    /**
     * {{ '@var' }} string
     */
    protected $headerSchema = '{{ $headerSchema }}';
@endif
@if (isset($querySchema))
@if (isset($headerSchema))

@endif
    /**
     * {{ '@var' }} string
     */
    protected $querySchema = '{{ $querySchema }}';
@endif
@if (isset($dataSchema))
@if (isset($headerSchema) || isset($querySchema))

@endif
    /**
     * {{ '@var' }} string
     */
    protected $dataSchema = '{{ $dataSchema }}';
@endif
@if (isset($routeSchema))
@if (isset($headerSchema) || isset($querySchema) || isset($dataSchema))

@endif
    /**
     * {{ '@var' }} string
     */
    protected $routeSchema = '{{ $routeSchema }}';
@endif
}
