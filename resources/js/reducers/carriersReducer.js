import { RECEIVED_CARRIER_LIST } from '../actions/types';

const initialState = {};

export default (state = initialState, action) => {
	switch (action.type) {
		case RECEIVED_CARRIER_LIST:
			console.log('RECEIVED_CARRIER_LIST');
			return {
				...state,
				carriers: action.payload
			}
		default:
			return state;
	}
}