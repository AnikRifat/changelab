<?php

namespace App\Lib;

use App\Models\Form;

class FormProcessor
{

    public $inputName = "form_generator";

    public function generatorValidation()
    {
        $validation['rules'] = [
            $this->inputName . 'is_required.*' => 'required|in:required,optional',
            $this->inputName . 'extensions.*'  => 'nullable',
            $this->inputName . 'options.*'     => 'nullable',
            $this->inputName . 'form_label.*'  => 'required',
            $this->inputName . 'form_type.*'   => 'required|in:text,select,radio,textarea,checkbox,file',
        ];

        $validation['messages'] = [
            $this->inputName . 'is_required.*.required' => 'All is required field is required',
            $this->inputName . 'is_required.*.in'       => 'Is required field is invalid',
            $this->inputName . 'form_label.*.required'  => 'All form label is required',
            $this->inputName . 'form_type.*.required'   => 'All form type is required',
            $this->inputName . 'form_type.*.in'         => 'Some selected form type is invalid',
        ];
        return $validation;
    }

    public function generate($act, $isUpdate = false, $identifierField = 'act', $identifier = null)
    {
        $name  = $this->inputName;
        $forms = request()->$name;
        $formData = [];

        if ($forms) {
            for ($i = 0; $i < count($forms['form_label']); $i++) {
                $extensions = $forms['extensions'][$i];
                if ($extensions != 'null' && $extensions != null) {
                    $extensionsArr = explode(',', $extensions);
                    $notMatchedExt = count(array_diff($extensionsArr, $this->supportedExt()));
                    if ($notMatchedExt > 0) {
                        throw new \Exception("Your selected extensions are invalid");
                    }
                }
                $label = titleToKey($forms['form_label'][$i]);
                $formData[$label] = [
                    'name' => $forms['form_label'][$i],
                    'label' => $label,
                    'is_required' => $forms['is_required'][$i],
                    'extensions' => $forms['extensions'][$i] == 'null' ? "" : $forms['extensions'][$i],
                    'options' => $forms['options'][$i] ? explode(",", $forms['options'][$i]) : [],
                    'type' => $forms['form_type'][$i],
                ];
            }
        }
        if ($isUpdate) {
            if ($identifierField == 'act') {
                $identifier = $act;
            }
            $form = Form::where($identifierField, $identifier)->first();
            if (!$form) {
                $form = new Form();
            }
        } else {
            $form = new Form();
        }
        $form->act = $act;
        $form->form_data = $formData;
        $form->save();
        return $form;
    }

    public function valueValidation($formData)
    {
        $validationRule = [];
        $rule = [];

        if ($formData != null) {
            foreach ($formData as $data) {
                if ($data->is_required == 'required') {
                    $rule = array_merge($rule, ['required']);
                } else {
                    $rule = array_merge($rule, ['nullable']);
                }
                if ($data->type == 'select' || $data->type == 'checkbox' || $data->type == 'radio') {
                    $rule = array_merge($rule, ['in:' . implode(',', $data->options)]);
                }
                if ($data->type == 'file') {
                    $rule = array_merge($rule, ['mimes:' . $data->extensions]);
                }
                if ($data->type == 'checkbox') {
                    $validationRule[$data->label . '.*'] = $rule;
                } else {
                    $validationRule[$data->label] = $rule;
                }
                $rule = [];
            }
        }

        return $validationRule;
    }

    public function processFormData($request, $formData)
    {
        $requestForm = [];
        if ($formData != null) {
            foreach ($formData as $data) {
                $name = $data->label;
                $value = $request->$name;
                if ($data->type == 'file') {
                    if ($request->hasFile($name)) {
                        $directory = date("Y") . "/" . date("m") . "/" . date("d");
                        $path = getFilePath('verify') . '/' . $directory;
                        $value = $directory . '/' . fileUploader($value, $path);
                    } else {
                        $value = null;
                    }
                }
                $requestForm[] = [
                    'name' => $data->name,
                    'type' => $data->type,
                    'value' => $value,
                ];
            }
        }
        return $requestForm;
    }

    public function supportedExt()
    {
        return [
            'jpg',
            'jpeg',
            'png',
            'pdf',
            'doc',
            'docx',
            'txt',
            'xlx',
            'xlsx',
            'csv'
        ];
    }
}
