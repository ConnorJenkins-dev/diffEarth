export interface Datatrace {
    x: number[];
    y: number[];
    type: string;
    name: string;
}

export function addDataTrace(
    x: number[],
    y: number[],
    type: string,
    name: string,
): Datatrace {
    return { x: x, y: y, type: type, name: name };
}
