
<!-- Modal -->
<div class="modal fade" id="formUserBackdrop{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Manager le compte {{ $item->code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="{{ url('change_user_role/' . $item->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <label>Attribuer un role<span class="text-danger">*</span></label>
                                                <select class="form-select" name="role_id" required>
                                                    @foreach (App\Models\Role::all() as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $item->role_id ? 'selected' : '' }}>
                                                            {{ $item->libelle }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="mb-3">
                                                <label>Région ordinale<span class="text-danger">*</span></label>
                                                <select name="region_ordinal_id" class="form-select">
                                                    @foreach (App\Models\RegionOrdinal::all() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->libelle }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success">Enregistrer</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
