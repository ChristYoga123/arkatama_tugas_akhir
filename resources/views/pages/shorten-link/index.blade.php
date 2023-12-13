<x-app-layout>
    @push('styles')
        <script src="{{ asset('sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.min.css') }}">
    @endpush
    <x-slot name="header">
        {{ __($title) }}
    </x-slot>

    <div class="mb-5">
        <button class="px-10 py-1 font-medium bg-[#7E3AF2] text-white rounded-lg hover:bg-purple-700 duration-200"
            onclick="addModal()">Create
            Link
        </button>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <div class="overflow-x-auto">
                    <table class="table">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" class="checkbox checkbox-all" />
                                    </label>
                                </th>
                                <th style="width: 50%">URL</th>
                                <th>Total Clicked</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($links as $link)
                                <tr>
                                    <th>
                                        <label>
                                            <input type="checkbox" class="checkbox" />
                                        </label>
                                    </th>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="mask mask-squircle w-12 h-12">
                                                    @php
                                                        // get hostname with http or https
                                                        $shortenedUrlHost = parse_url($link->original_url, PHP_URL_HOST);
                                                        // get favicon
                                                        $favicon = "https://www.google.com/s2/favicons?sz=64&domain_url=" . $shortenedUrlHost;

                                                    @endphp
                                                    <img src="{{ $favicon }}"
                                                        alt="Favicon {{ $shortenedUrlHost }}"
                                                        width="50px" />
                                                </div>
                                            </div>
                                            <div>
                                                <a target="_blank" href="{{ $hostname }}/s/{{ $link->shortened_url }}" id="short_url_{{ $link->shortened_url }}" class="font-bold short_url text-blue-700">{{ $hostname }}/s/{{ $link->shortened_url }}</a>
                                                <div class="text-sm opacity-50 original_url">{{ $link->original_url }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $link->total_visits }}</td>
                                    <th>
                                        <div class="flex gap-3">
                                            <button class="copy_{{ $link->shortened_url }} py-1 px-5 border border-[#DBE0EB] rounded-lg"
                                                onclick="copy('{{ $link->shortened_url }}')">Copy</button>
                                            <button class="py-1 px-5 border border-warning rounded-lg bg-white"
                                                onclick="editModal()">Edit</button>
                                            <form action="{{ route("shorten-link.destroy", $link->shortened_url) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="button"
                                                    class="py-1 px-5 border border-error rounded-lg bg-white" onclick="destroy('{{ $link->shortened_url }}')">Delete
                                                </button>
                                            </form>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- foot -->
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>URL</th>
                                <th>Total Clicked</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Shorten URL --}}
    <dialog id="modal_form" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg title"></h3>

            <form method="POST">
                @csrf
                <div class="flex flex-col gap-5 mt-3">
                    <div class="flex flex-col gap-1">
                        <label for="destination">Destination (*)</label>
                        <input type="url" class="input input-bordered" placeholder="Input your long url" required
                            name="original_url">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="destination">Shortened</label>
                        <div class="flex gap-1">
                            <input type="url" class="input input-bordered" placeholder="{{ $hostname }}"
                                disabled>
                            <div class="divider lg:divider-horizontal">/</div>
                            <input type="text" class="input input-bordered" placeholder="Optional" name="custom_url">
                        </div>
                    </div>

                    <div>
                        <button
                            type="button"
                            class="py-1 px-5 bg-[#7E3AF2] rounded-lg text-white font-medium hover:bg-purple-700 duration-200">Create
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </dialog>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('.checkbox-all').click(function() {
                    if ($(this).is(':checked')) {
                        $('.checkbox').prop('checked', true);
                    } else {
                        $('.checkbox').prop('checked', false);
                    }
                });
            });

            function addModal() {
                document.getElementById('modal_form').showModal();
                $(".title").html("Create Link");
                // beri properti onclick
                $("form button[type=button]").attr("onclick", "store()");
            }

            function editModal() {
                document.getElementById('modal_form').showModal();
                $(".title").html("Edit Link");
                $("form button[type=button]").attr("onclick", "update()");
            }

            function destroy(link) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('shorten-link.destroy', '') }}" + "/" + link,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: "DELETE",
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: response.message,
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: xhr.responseJSON.message,
                                });
                            }
                        });
                    }
                });
            }

            function copy(link) {
                const copyText = document.querySelector("#short_url_" + link);
                navigator.clipboard.writeText(copyText.textContent);
                // text berubah menjadi copied dan ketika setelah 2 detik kembali ke copy
                $(`.copy_${link}`).addClass("border border-success bg-success text-white");
                $(`.copy_${link}`).html("Copied");
                setTimeout(function() {
                    $(`.copy_${link}`).removeClass("border border-success bg-success text-white");
                    $(`.copy_${link}`).addClass("border border-[#DBE0EB]");
                    $(`.copy_${link}`).html("Copy");
                }, 2000);
            }

            function closeModal()
            {
                document.getElementById('modal_form').close();
                // clear modal_form
                $("input[name=original_url]").val("");
                $("input[name=custom_url]").val("");
            }

            function store() {
                $.ajax({
                    url: "{{ route('shorten-link.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        original_url: $("input[name=original_url]").val(),
                        custom_url: $("input[name=custom_url]").val(),
                    },
                    beforeSend: function() {
                        closeModal();
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
