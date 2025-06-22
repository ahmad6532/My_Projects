import {
    DraggableAttributes,
    DraggableNode,
    DraggableSyntheticListeners,
    UseDraggableArguments,
} from "@dnd-kit/core";
import { TextFieldFormElement } from "./fields/TextField";
import { TitleFieldFormElement } from "./fields/TitleField";
import { SubTitleFieldFormElement } from "./fields/SubTitleField";
import { ParagraphFieldFormElement } from "./fields/ParagraphField";
import { SeparatorFieldFormElement } from "./fields/Separator";
import { SpacerFieldFormElement } from "./fields/Spacer";
import { NumberFieldFormElement } from "./fields/NumberField";
import { TextAreaFormElement } from "./fields/TextAreaField";
import { DateFieldFormElement } from "./fields/DateField";
import { SelectFieldFormElement } from "./fields/SelectField";
import { CheckboxFieldFormElement } from "./fields/CheckboxField";
import { DMDBlock } from "./fields/DmdBlock";

export interface ConditionType {
    id: string;
    questionId: string;
    operator: string;
    logicalOperator: string;
    value: string;
    type: string;
    connectedWith?: string; // ID of the condition this one is connected with
}
export type ElementsType =
    | "TextField"
    | "TitleField"
    | "SubTitleField"
    | "ParagraphField"
    | "SeparatorField"
    | "SpacerField"
    | "NumberField"
    |'TextAreaField'
    |'DateField'
    |'SelectField'
    |'CheckboxField'
    |'DMDField';
export type SubmitFunction = (key: string, value: string) => void;

export type FormElement = {
    type: ElementsType;
    construct: (id: string) => FormElementInstance;

    designerBtnElement: {
        icon: React.ReactNode;
        label: string;
    };

    designerComponent: React.FC<{
        pageId:string;
        elementInstance: FormElementInstance;
        listeners?: DraggableSyntheticListeners;
        attributes?: DraggableAttributes;
    }>;
    formComponent: React.FC<{
        elementInstance: FormElementInstance;
        submitValue?: SubmitFunction;
        isInvalid?: boolean;
        defaultValue?: string;
    }>;
    propertiseComponent: React.FC<{
        pageId:string;
        elementInstance: FormElementInstance;
    }>;

    validate: (
        formElement: FormElementInstance,
        currentValue: string
    ) => boolean;
};

type FormElementsType = {
    [key in ElementsType]: FormElement;
};

export type FormElementInstance = {
    id: string;
    type: ElementsType;
    extraAttributes?: Record<string, any>;
    conditions?: ConditionType[];
};
export type FormPage = {
    id: string;
    name: string;
    description:string;
    questions: FormElementInstance[];
}
export const FormElements: FormElementsType = {
    TextField: TextFieldFormElement,
    TitleField: TitleFieldFormElement,
    SubTitleField: SubTitleFieldFormElement,
    ParagraphField: ParagraphFieldFormElement,
    SeparatorField: SeparatorFieldFormElement,
    SpacerField: SpacerFieldFormElement,
    NumberField: NumberFieldFormElement,
    TextAreaField:TextAreaFormElement,
    DateField: DateFieldFormElement,
    SelectField: SelectFieldFormElement,
    CheckboxField: CheckboxFieldFormElement,
    DMDField:DMDBlock
};
