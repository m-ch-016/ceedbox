<h2>
    {{ $job->title}}
</h2>

<p>
    Congrats! Your job is live on the website!
</p>

<a href="{{ url('/jobs/'. $job->id) }}">View your job</a>
