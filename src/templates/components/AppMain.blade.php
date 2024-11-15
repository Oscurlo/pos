<?php use Src\Config\Session; ?>

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $props->title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end" translate="no">
                        {{ @foreach ($split_breadcrumb as $key => $value): }}
                            {{ @if ($key === ($count_breadcrumb - 1)): }}
                                <li class="breadcrumb-item active" aria-current="page"> {{ $value }} </li>
                            {{ @else: }}
                                <li class="breadcrumb-item"><a href="#">{{ $value }}</a></li>
                            {{ @endif }}
                        {{ @endforeach }}
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content" data-csrf="{{ $csrf }}">
        <div class="container-fluid">
            {{ $props->children }}
        </div>
    </div>
</main>