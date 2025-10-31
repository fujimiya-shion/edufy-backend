<?php
namespace App\Traits;
trait UsesFilamentServiceCrud {
    protected function getService()
    {
        /** @var \App\Filament\Resources\BaseResource $resource */
        $resource = static::getResource();
        return $resource::getService();
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->getService()->create($data);
    }

    protected function handleRecordUpdate($record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->getService()->update($record->id, $data);
    }

    protected function handleRecordDeletion($record): void
    {
        $this->getService()->delete($record->id);
    }
}