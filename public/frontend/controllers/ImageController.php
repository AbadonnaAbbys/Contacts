<?php

namespace frontend\controllers;

use common\models\Image;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ImageController extends Controller
{
    /**
     * @param integer $id
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->id) {
            return $this->redirect(['site/login']);
        }

        $image = Image::findOne($id);

        if (!$image || $image->contact->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('File not found');
        }

        return Yii::$app->response->sendFile($image->getFilePath(), $image->original_filename, ['inline' => true])
                                  ->send();
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionThumbnail($id)
    {
        if (!Yii::$app->user->id) {
            return $this->redirect(['site/login']);
        }

        $image = Image::findOne($id);

        if (!$image || $image->contact->user_id != Yii::$app->user->id) {
            throw new NotFoundHttpException('File not found');
        }

        return Yii::$app->response->sendFile($image->getThumbnailPath(), $image->original_filename, ['inline' => true])
                                  ->send();
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $error = '';

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                /** @var Image $image */
                $image = (new Image())::findOne(['id' => $id]);
                if ($image && $image->contact->user_id == Yii::$app->user->id) {
                    try {
                        $image->delete();
                    }
                    catch (StaleObjectException $e) {
                        $error = 'Can\'t delete';
                    }
                    catch (\Throwable $e) {
                        $error = 'Can\'t delete';
                    }
                }
            }
            else {
                $error = 'ID not set';
            }
        }

        return $this->asJson([
                                 'success' => empty($error),
                                 'error' => $error,
                             ]);
    }
}