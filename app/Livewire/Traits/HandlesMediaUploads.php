<?php

namespace App\Livewire\Traits;

trait HandlesMediaUploads
{
    /**
     * Uploaded media files.
     *
     * @var array
     */
    public $media = [];

    /**
     * Remove a media item by its index.
     */
    public function removeMedia(int|string $index): void
    {
        $idx = (int) $index;
        if (isset($this->media[$idx])) {
            unset($this->media[$idx]);
            $this->media = array_values($this->media);
        }
    }

    /**
     * Standard media validation rules for forms.
     */
    protected function getMediaRules(): array
    {
        return [
            'media' => 'nullable|array|max:5',
            'media.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm',
                'max:20480', // 20MB
            ],
        ];
    }

    /**
     * Standard media validation messages.
     */
    protected function getMediaMessages(): array
    {
        return [
            'media.max' => 'Maksimal 5 file yang dapat diupload.',
            'media.*.max' => 'Ukuran file maksimal 20MB.',
            'media.*.mimes' => 'Format file harus berupa gambar (jpg, jpeg, png, webp) atau video (mp4, mov, avi, webm).',
        ];
    }

    /**
     * Determine if an uploaded file is a video using server-side MIME type detection.
     */
    protected function isVideoFile($file): bool
    {
        try {
            return str_starts_with($file->getMimeType(), 'video/');
        } catch (\Throwable) {
            return in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'avi', 'webm']);
        }
    }
}
