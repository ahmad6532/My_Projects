import {
    Stack,
    Typography,
    Button,
    IconButton,
} from "@mui/material";
import React, { useRef, useState, useEffect } from "react";
import useDesigner from "../hooks/useDesigner";
import Select, { StylesConfig } from "react-select";
import { FormElementInstance, FormElements } from "../FormElements";
import DeleteIcon from "@mui/icons-material/Delete";
import CondtionActions from "./CondtionActions";

const style:StylesConfig<any,false> = {
    control: (baseStyles, state) => ({
        ...baseStyles,
        width: 'fit-content',
        height: "36px",
        border:'none',
        outline: 'none',
        borderRadius:'calc(12.5 * 8px)',
        boxShadow: 'none',
        background: state.isDisabled ? '#E6E6E6' :'#FAEFE2',
        cursor: state.isDisabled ? 'not-allowed' : 'pointer',
        '&:hover':{
            backgroundColor: state.isDisabled ? '#E6E6E6' :'#FF9814',
        },
        fontSize:'18px'
    }),
    menu: (baseStyles) => ({
        ...baseStyles,
        zIndex: 10,
        width:'250%'
    }),
    indicatorsContainer: (baseStyles) => ({
        ...baseStyles,
        display:'none'
    }),
    option: (baseStyles, state) => ({
        ...baseStyles,
        backgroundColor: state.isDisabled
            ? "#f6f6f6"
            : state.isSelected
            ? "#6BC1B7"
            : state.isFocused
            ? "#f0f0f0"
            : undefined,
        color: state.isDisabled
            ? "#a0a0a0"
            : state.isSelected
            ? "#ffffff"
            : "#000000",
        cursor: state.isDisabled ? 'not-allowed' : 'default',
        "&:hover": {
            backgroundColor: !state.isDisabled && state.isFocused
                ? "#e0e0e0"
                : undefined,
        },
    }),
    singleValue: (baseStyles, state) => ({
        ...baseStyles,
        color: state.isDisabled ? "#a0a0a0" : "#000000",
        
    }),
    input: (baseStyles) => ({
        ...baseStyles,
        color: "#000000",
    }),
    placeholder: (baseStyles) => ({
        ...baseStyles,
        color: "#a0a0a0", // Placeholder text color
    }),
    
};

export interface QuestionWithPageId {
    pageId: string;
    question: FormElementInstance;
}

export interface Condition {
    id: string;
    questionId: string;
    operator: string;
    logicalOperator: string;
    value: string;
    type: string; // Action Type
    connectedWith?: string; // ID of the condition this one is connected with
    showPageId?:string;
    hidePageId?:string;
    showQuestionId?:string;
    hideQuestionId?:string;
    sendEmail?:{
        emailOption?:string;
        freeTypeEmail?:string;
        content?:string;
    };
}

let conditionsList = [
    "empty",
    "not empty",
    "equals",
    "does not equal",
    "contains",
    "not contains",
    "greater than",
    "less than",
    "greater than or equal to",
    "less than or equal to",
];

const actionOptions = [
    'Show Page',
    'Hide Page',
    'Show Question',
    'Hide Question',
    'Send Email',
    'Add Priority Value',
    'Trigger Close form'
];

const Conditions = ({ editableConditions,handleCloseDialog }: { editableConditions?: Condition[],handleCloseDialog?: () => void }) => {
    const formValues = useRef<{ [key: string]: string }>({});
    const {
        elements,
        selectedItem,
        setElements,
        selectedPageId,
        updateElement,
        setSelectedItem
    } = useDesigner();

    const [conditions, setConditions] = useState<Condition[]>(editableConditions || [
        { id: generateUniqueId(), questionId: "", operator: "equals", logicalOperator: "", value: "", type: '' },
    ]);

    const [selectedAction, setSelectedAction] = useState<string>('');

    const allQuestionsWithPageId: QuestionWithPageId[] = elements.flatMap(
        (page) =>
            page.questions.map((question) => ({
                pageId: page.id,
                question: question,
            }))
    );

    function generateUniqueId() {
        return Math.random().toString(36).substr(2, 9);
    }

    const handleConditionChange = (
        index: number,
        field: keyof Condition,
        value: string
    ) => {
        const updatedConditions = [...conditions];
        updatedConditions[index][field] = value;
        setConditions(updatedConditions);
    };

    const addCondition = () => {
            const firstConditionId = conditions[0].id;
            setConditions([
                ...conditions,
                { id: generateUniqueId(), questionId: "", operator: "", logicalOperator: "AND", value: "", type: selectedAction, connectedWith: firstConditionId },
            ]);
    };

    const removeCondition = (index: number) => {
        const updatedConditions = conditions.filter((_, i) => i !== index);
        setConditions(updatedConditions);
    };

    const submitValues = (id: string, value: string, index: number) => {
        formValues.current[id] = value;
        const updatedConditions = [...conditions];
        updatedConditions[index].value = value;
        setConditions(updatedConditions);
    };

    const updateElements = () => {
        if(!selectedItem) return;
        if(editableConditions){
            const question = elements.find((page) => page.id === selectedPageId)?.questions.find((question) => question.id === selectedItem.id)!;

        const existingConditions = question?.conditions || [];
        const editableConditionIds = new Set(editableConditions?.map(cond => cond.id));
        const filteredExistingConditions = existingConditions.filter(cond => !editableConditionIds.has(cond.id));
        const updatedConditions = [...filteredExistingConditions, ...conditions];
        const updatedQuestion = { ...question, conditions: updatedConditions };
        updateElement(selectedPageId, selectedItem.id, updatedQuestion);
        setSelectedItem(updatedQuestion);
        }else{
                const question = elements.find((page) => page.id === selectedPageId)?.questions.find((question) => question.id === selectedItem.id)!;
                const updatedQuestion = { ...question, conditions: [...(question?.conditions || []), ...conditions] };
                updateElement(selectedPageId, selectedItem?.id, updatedQuestion);
                setSelectedItem(updatedQuestion);
        }
    };

    useEffect(() => {
        if (editableConditions) {
            setConditions(editableConditions);
            setSelectedAction(editableConditions[0].type);
        }
    }, [editableConditions]);


    return (
        <Stack sx={{ minHeight: "300px" }}>
            {conditions.map((condition, index) => {
                const selectedConditionalElement = index === 0 && selectedItem
                    ? selectedItem
                    : allQuestionsWithPageId.find(
                        (item) => item.question.id === condition.questionId
                    )?.question;

                let DesignerElement: any;
                if (selectedConditionalElement) {
                    DesignerElement =
                        FormElements[selectedConditionalElement.type].formComponent;

                }

                return (
                    <Stack key={condition.id} mt={2}>
                        <Stack direction="row" alignItems="center" gap={1}>
                            {index === 0 && (
                                <Typography>If</Typography>
                            )}
                            {index > 0 && (
                                <>
                                    <Select
                                        isSearchable={false}
                                        classNamePrefix="react-select-custom"
                                        menuPosition="fixed"
                                        value={{ value: condition.logicalOperator, label: condition.logicalOperator }}
                                        onChange={(e) => {
                                            if (e) {
                                                handleConditionChange(index, "logicalOperator", e.value);
                                            }
                                        }}
                                        styles={style}
                                        defaultValue={{ value: "AND", label: "AND" }}
                                        options={[
                                            { value: "AND", label: "AND" },
                                            { value: "OR", label: "OR" },
                                        ]}
                                    />
                                    <IconButton
                                        color="error"
                                        onClick={() => removeCondition(index)}
                                    >
                                        <DeleteIcon />
                                    </IconButton>
                                </>
                            )}
                            <Select
                                isSearchable={false}
                                classNamePrefix="react-select-custom"
                                menuPosition="fixed"
                                value={
                                    index === 0 && selectedItem
                                    ? {
                                        value: selectedItem.id,
                                        label: selectedItem.id,
                                    }
                                    : condition.questionId
                                        ? {
                                            value: condition.questionId,
                                            label: condition.questionId,
                                        }
                                        : null
                                }
                                onChange={(e) => {
                                    if (e) {
                                        handleConditionChange(
                                            index,
                                            "questionId",
                                            e.value
                                        );
                                    }
                                }}
                                isDisabled={index === 0}
                                isOptionDisabled={value =>  index !== 0 && value.value === selectedItem?.id}
                                styles={style}
                                options={allQuestionsWithPageId.map(
                                    (element) => ({
                                        value: element.question.id,
                                        label: element.question.id,
                                    })
                                )}
                            />
                            <Select
                                isSearchable={false}
                                classNamePrefix="react-select-custom"
                                menuPosition="fixed"
                                className="w-50"
                                value={
                                    condition.operator
                                        ? { value: condition.operator, label: condition.operator }
                                        : null
                                }
                                onChange={(e) => {
                                    if (e) {
                                        handleConditionChange(
                                            index,
                                            "operator",
                                            e.value
                                        );
                                    }
                                }}
                                styles={style}
                                options={conditionsList.map((element) => ({
                                    value: element,
                                    label: element,
                                }))}
                            />
                        </Stack>
                        {selectedConditionalElement && 
                        (condition.operator !== 'empty' && condition.operator !== 'not empty') && (
                            <Stack mt={2}>
                                <DesignerElement
                                    elementInstance={selectedConditionalElement}
                                    submitValue={(id: string, value: string) => submitValues(id, value, index)}
                                    defaultValue={condition.value}
                                />
                            </Stack>
                        )}

                    </Stack>
                );
            })}

            <Button sx={{ alignSelf: 'flex-start', my: 2, color: 'white' }} variant="contained" onClick={addCondition}>
                Add Condition
            </Button>
            <Stack direction='row' gap={1} alignItems='center' flexWrap='wrap'>
                <Typography>Then</Typography>
                <Select
                classNamePrefix="react-select-custom"
                    menuPosition="fixed"
                    value={selectedAction ? { value: selectedAction, label: selectedAction } : null}
                    onChange={(e) => {
                        if (e) {
                            setSelectedAction(e.value);
                            // Update all existing conditions with the new action type
                            setConditions(conditions.map(condition => ({
                                ...condition,
                                type: e.value
                            })));
                        }
                    }}
                    styles={style}
                    options={actionOptions.map((option) => ({
                        value: option,
                        label: option,
                    }))}
                />
                <CondtionActions thenAction={selectedAction} conditions={conditions} setConditions={setConditions}/>
            </Stack>
            <Stack direction="row" gap={1} justifyContent='flex-end'>
                <Button
                    sx={{width:'fit-content' }}
                    variant="outlined"
                    onClick={handleCloseDialog}
                    size="large"
                >
                    Cancel
                </Button>
                <Button
                    sx={{ color: 'white',width:'fit-content' }}
                    variant="contained"
                    onClick={()=>{updateElements(); handleCloseDialog?.()} }
                    size="large"
                >
                    Apply
                </Button>

            </Stack>
        </Stack>
    );
};

export default Conditions;
