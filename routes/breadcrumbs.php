<?php

Breadcrumbs::macro('resourceUsers', function ($name, $title) {
    // Home > [User]
    Breadcrumbs::for("$name.index", function ($trail) use ($name, $title) {
        $trail->parent('home');
        $trail->push($title, route("$name.index"));
    });

    // Home > [User] > Tambah
    Breadcrumbs::for("$name.create", function ($trail) use ($name) {
        $trail->parent("$name.index");
        $trail->push('Tambah', route("$name.create"));
    });

    // Home > [User] > [Kode - Nama User]
    Breadcrumbs::for("$name.show", function ($trail, $model) use ($name) {
        $trail->parent("$name.index");
        $trail->push($model->username . ' - ' . $model->authable->nama, route("$name.show", $model->username));
    });

    // Home > [User] > [Kode - Nama User] > Edit
    Breadcrumbs::for("$name.edit", function ($trail, $model) use ($name) {
        $trail->parent("$name.show", $model);
        $trail->push('Edit', route("$name.edit", $model->username));
    });
});

Breadcrumbs::macro('resourceJadwal', function ($name, $title) {
    // Home > [Jadwal]
    Breadcrumbs::for("$name.index", function ($trail) use ($name, $title) {
        $trail->parent('home');
        $trail->push($title, route("$name.index"));
    });

    // Home > [Jadwal] > Tambah
    Breadcrumbs::for("$name.create", function ($trail) use ($name) {
        $trail->parent("$name.index");
        $trail->push('Tambah', route("$name.create"));
    });

    // Home > [Jadwal] > [Kode - Nama Matkul]
    Breadcrumbs::for("$name.show", function ($trail, $model) use ($name) {
        $trail->parent("$name.index");
        $trail->push($model->matkul->kode . ' - ' . $model->matkul->nama, route("$name.show", $model));
    });

    // Home > [Jadwal] > [Kode - Nama Matkul] > Edit
    Breadcrumbs::for("$name.edit", function ($trail, $model) use ($name) {
        $trail->parent("$name.show", $model);
        $trail->push('Edit', route("$name.edit", $model));
    });
});

Breadcrumbs::macro('resourceMatkul', function ($name, $title) {
    // Home > Matkul
    Breadcrumbs::for("$name.index", function ($trail) use ($name, $title) {
        $trail->parent('home');
        $trail->push($title, route("$name.index"));
    });

    // Home > Matkul > Tambah
    Breadcrumbs::for("$name.create", function ($trail) use ($name) {
        $trail->parent("$name.index");
        $trail->push('Tambah', route("$name.create"));
    });

    // Home > Matkul > [Kode - Nama Matkul]
    Breadcrumbs::for("$name.show", function ($trail, $model) use ($name) {
        $trail->parent("$name.index");
        $trail->push($model->kode . ' - ' . $model->nama, route("$name.show", $model));
    });

    // Home > Matkul > [Kode - Nama Matkul] > Edit
    Breadcrumbs::for("$name.edit", function ($trail, $model) use ($name) {
        $trail->parent("$name.show", $model);
        $trail->push('Edit', route("$name.edit", $model));
    });
});

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > Profile
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('home');
    $trail->push('Profile', route('profile.users'));
});

// Home > Ubah Password
Breadcrumbs::for('password', function ($trail) {
    $trail->parent('home');
    $trail->push('Ubah Password', route('password.users'));
});

// Home > Jadwal Dosen
Breadcrumbs::for('jadwaldosen', function ($trail) {
    $trail->parent('home');
    if (auth()->user()->getRole() == 'admin') {
        $trail->push('Jadwal', route('jadwal.index'));
    } else {
        $trail->push('Jadwal Dosen', route('jadwal.dosen.index'));
    }
});

// Home > Jadwal Dosen > [Nama Matkul]
Breadcrumbs::for('pertemuan', function ($trail, $jadwal) {
    $trail->parent('jadwaldosen');
    $trail->push($jadwal->matkul->kode . ' - ' . $jadwal->matkul->nama, route("jadwal.pertemuan", $jadwal));
});

// Home > Jadwal Dosen > [Nama Matkul] > Pertemuan / Buat Jurnal
Breadcrumbs::for('jurnal', function ($trail, $jadwal, $jurnal) {
    $trail->parent('pertemuan', $jadwal);
    $trail->push('Pertemuan ke - ' . $jurnal->pertemuan, route('jadwal.jurnal.create', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]));
});

// Home > Jadwal Dosen > [Nama Matkul] > Absensi
Breadcrumbs::for('absensi', function ($trail, $jadwal, $jurnal) {
    $trail->parent('pertemuan', $jadwal);
    $trail->push('Absensi', route('jadwal.absensi.index', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]));
});


// Dosen & Mahasiswa resource
Breadcrumbs::resourceUsers('dosen', 'Dosen');
Breadcrumbs::resourceUsers('mahasiswa', 'Mahasiswa');

// Jadwal & Matkul resource
Breadcrumbs::resourceJadwal('jadwal', 'Jadwal');
Breadcrumbs::resourceMatkul('matkul', 'Matkul');
