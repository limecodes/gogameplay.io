import { RECEIVED_CARRIER_LIST_SUCCESS } from '../actions/types';

const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case RECEIVED_CARRIER_LIST_SUCCESS:
			return action.payload;
		default:
			return state;
	}
}