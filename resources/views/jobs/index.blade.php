<x-layout>
    <x-slot:heading>Job Listings</x-slot:heading>

    <div class="space-y-4 rounded-md">
        @foreach ($jobs as $job)
        <a href="/jobs/{{$job['id']}}" class="block px-4 py-6 border border-gray-300">
            <div class="italic font-bold text-blue-500">{{$job->employer->name}}</div>
                <strong>{{ $job['title'] }}</strong>: pays £{{ $job['salary']}} per year
            </a>
        @endforeach
    </div>

    <div class="">
        {{$jobs->links()}}
    </div>

</x-layout>
