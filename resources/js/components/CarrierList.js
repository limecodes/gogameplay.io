import React from 'react';

const CarrierList = ({ carriers }) => {
	return carriers.map((carrier, i) => {
		return <option key={ i } name={ carrier.name }>{ carrier.name }</option>
	});
}

export default CarrierList;