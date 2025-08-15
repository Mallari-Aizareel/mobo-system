@extends('adminlte::page')

@section('title', 'Agency FAQs')

@section('content_header')
    <h1>Frequently Asked Questions</h1>
@endsection

@section('content')
<div class="container-fluid">
    @if($faqs->isEmpty())
        <div class="alert alert-info">
            No FAQs available at the moment.
        </div>  
    @else
        <div id="faqAccordion">
            @foreach($faqs as $index => $faq)
                <div class="card mb-2 shadow-sm">
                    <div class="card-header" id="heading{{ $index }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link d-flex justify-content-between align-items-center w-100 @if($index != 0) collapsed @endif" 
                                    data-toggle="collapse" 
                                    data-target="#collapse{{ $index }}" 
                                    aria-expanded="@if($index == 0) true @else false @endif" 
                                    aria-controls="collapse{{ $index }}">
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down rotate-icon"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse{{ $index }}" class="collapse @if($index == 0) show @endif" aria-labelledby="heading{{ $index }}" data-parent="#faqAccordion">
                        <div class="card-body faq-answer">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('css')
<style>
button[aria-expanded="true"] .rotate-icon {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

.rotate-icon {
    transition: transform 0.3s ease;
}

.faq-answer {
    padding-left: 1.5rem;
    padding-right: 1rem;
    margin-left: 0.5rem;
    background-color: #f9f9f9;
    border-left: 3px solid #007bff;
    border-radius: 0 0.25rem 0.25rem 0;
}
</style>
@stop
