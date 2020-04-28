import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

class Instructions extends Component {

	render() {
		const DivCheck = () => { 
			return (
				<div className="badge badge-success">Check</div>
			) 
		};
		return (
			<div className="col-12">
				<small>
					<p><strong>Instructions</strong></p>
					<ul>
						<li>{(this.props.validation.platform) ? <DivCheck /> : '1.'}{' '}Confirm your device platform { this.props.validation.platform }</li>
						<li>{(this.props.validation.carrier) ? <DivCheck /> : '2.'}{' '}Confirm your carrier</li>
						<li>{(this.props.validation.search) ? <DivCheck /> : '3.'}{' '}Click button at the bottom to search for coupon</li>
					</ul>
				</small>
			</div>
		);
	}
}

const mapStateToProps = state => ({
	validation: state.validation
});

export default connect(mapStateToProps, {})(Instructions);