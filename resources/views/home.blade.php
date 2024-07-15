<x-layout href='/'>
    <x-slot:heading>Home Page</x-slot:heading>

    <form action="{{ route('trigger.make.scenario') }}" method="GET">
        @csrf
        <button type="submit">Trigger Make.com Scenario</button>
    </form>


</x-layout>
