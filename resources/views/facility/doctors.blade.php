<h1>Doctors Management</h1>

<p>This page lists all doctors associated with the facility. You can view their details, manage their information, and perform various actions related to doctor management.</p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($doctors as $doctor)
        <tr>
            <td>{{ $doctor->id }}</td>
            <td>{{ $doctor->name }}</td>
            <td>{{ $doctor->specialization }}</td>
            <td>{{ $doctor->contact }}</td>
            <td>
                <a href="{{ route('doctors.show', $doctor->id) }}">View</a>
                <a href="{{ route('doctors.edit', $doctor->id) }}">Edit</a>
                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('doctors.create') }}">Add New Doctor</a>