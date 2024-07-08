<x-layout>
    <x-slot:title>
        Job
    </x-slot:title>

    <x-slot:heading>Job</x-slot:heading>
    <h2> <strong>{{ $job->title }}</strong></h2>
    <p>
        This pays £{{ $job->salary }}
    </p>

    @can('edit', $job)
        <p class="mt-6">
            <x-button href="/jobs/ {{ $job->id }}/edit">Edit Job</x-button>
        </p>
    @endcan

</x-layout>
