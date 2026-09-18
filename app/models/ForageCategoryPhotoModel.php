<?php

class ForageCategoryPhotoModel extends \Asatru\Database\Model {
    public static function uploadPhoto($categoryId, $label, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            if ((!isset($_FILES['photo'])) || ($_FILES['photo']['error'] !== UPLOAD_ERR_OK)) {
                throw new \Exception('Errorneous file');
            }

            $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

            if ($file_ext === null) {
                throw new \Exception('File is not a valid image');
            }

            $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

            move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

            if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                throw new \Exception('createThumbFile failed');
            }

            static::raw('INSERT INTO `@THIS` (category, author, thumb, original, label) VALUES(?, ?, ?, ?, ?)', [
                $categoryId, (($user) ? $user->get('id') : 0), $file_name . '_thumb.' . $file_ext, $file_name . '.' . $file_ext, $label
            ]);

            $recent = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();
            if ($recent) {
                return $recent->get('id');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getCategoryGallery($categoryId)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE category = ? ORDER BY id DESC', [$categoryId]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function removePhoto($photo, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $photo_data = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$photo])->first();
            if (!$photo_data) {
                return;
            }

            if (file_exists(public_path('/img/' . $photo_data->get('original')))) {
                unlink(public_path('/img/' . $photo_data->get('original')));
            }

            if (file_exists(public_path('/img/' . $photo_data->get('thumb')))) {
                unlink(public_path('/img/' . $photo_data->get('thumb')));
            }

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$photo]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function setAsMainPhoto($id)
    {
        try {
            $photo_data = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$photo_data) {
                return;
            }

            ForageCategoryModel::setPhoto($photo_data->get('category'), $photo_data->get('thumb'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function clearForCategory($categoryId)
    {
        try {
            $rows = static::raw('SELECT * FROM `@THIS` WHERE category = ?', [$categoryId]);
            foreach ($rows as $row) {
                if (file_exists(public_path('/img/' . $row->get('original')))) {
                    unlink(public_path('/img/' . $row->get('original')));
                }

                if (file_exists(public_path('/img/' . $row->get('thumb')))) {
                    unlink(public_path('/img/' . $row->get('thumb')));
                }

                static::raw('DELETE FROM `@THIS` WHERE id = ?', [$row->get('id')]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
